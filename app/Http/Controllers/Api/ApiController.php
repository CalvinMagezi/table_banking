<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;

class ApiController extends Controller
{

    protected $statusCode = 200;

    /**
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * The response status code
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Gives the resource collection with pagination
     * @param LengthAwarePaginator $items
     * @param $data
     * @return mixed
     */

    protected function respondWithPagination(LengthAwarePaginator $items, $data)
    {
        $data = array_merge($data,[
            'paginator' => [
                'total_count' 	=> $items->total(),
                'total_pages' 	=> ceil($items->total() / $items->perPage()),
                'current_page'	=>	$items->currentPage(),
                'limit'			=>	$items->perPage()
            ]
        ]);

        return $this->respond($data);

    }

    /**
     * When a missing resource is requested
     * @param string $message
     * @return mixed
     */
    public function respondNotFound($message = "Not Found !")
    {
        return $this->setStatusCode(404)->respondWithError($message);
    }

    /**
     * When a non supported search parameter is requested
     * @param string $message
     * @return mixed
     */
    public function respondWrongParameter ($message = "You requested a non supported search parameter!")
    {
        return $this->setStatusCode(400)->respondWithError($message);
    }

    /**
     * There was an internal error
     * @param string $message
     * @return mixed
     */
    public function respondInternalError($message = "Internal Server Error !!")
    {
        return $this->setStatusCode(500)->respondWithError($message);
    }


    /**
     * Give json feedback with status code
     * @param $data
     * @param array $headers
     * @return mixed
     */
    public function respond($data, $headers = [])
    {
        return \Response::json($data, $this->getStatusCode(), $headers);

    }

    /**
     * respond with a generic error
     * @param string $message
     * @return mixed
     */
    public function respondWithError($message  = 'There was an error')
    {
        return $this->respond([
            'error' => [
                'error'         => true,
                'message'       => $message,
                'status_code'   => $this->getStatusCode()
            ]
        ]);

    }


    /**
     * respond with a generic error
     * @param string $message
     * @return mixed
     */
    public function respondWithSuccessCode($message  = 'Success !!')
    {
        return $this->respond([
            'data' => [
                'error'         => false,
                'message'       => $message,
                'status_code'   => $this->getStatusCode()
            ]
        ]);

    }

    /**
     * Some operation (save only?) has completed successfully
     * @param string $message
     * @return mixed
     */
    public function respondWithSuccess($message = 'Success !!')
    {
        return $this->respond( $message );
    }

    /**
     * Some operation (save) failed.
     * @param string $message
     * @return mixed
     */
    public function respondNotSaved($message = "Not Saved !")
    {
        return $this->setStatusCode(400)->respondWithError($message);
    }


    /**
     * Cleans up url variables to eliminate spaces
     * @param $string
     * @return array
     */
    public function formatFields($string)
    {
        //return explode(",", preg_replace('/\s+/', '', rtrim(trim($string),',')));

        return explode(",", preg_replace('/\s*,\s*/', ',', rtrim(trim($string), ',')));
    }

    /**
     * Filter data on endpoint by use of fields
     * @param $request
     * @param $repository
     * @param $transformer
     * @return bool|mixed
     */
    public function doFilter($request, $repository, $transformer)
    {
        do
        {
            if($request->has('field')) {
                $fieldName = $request->input('field');

                if(!$transformer->reverse($fieldName)){
                    return $this->respondNotFound('Filter field is invalid.');
                }else{

                    $field = $transformer->reverse($fieldName);

                    $data = $repository->getManyWhere($field, $this->formatFields($request->input('value')) );

                    return $this->respondWithPagination($data, [
                        'data' => $transformer->transformCollection($data->all())
                    ]);

                }

            }else{
                return false;
            }
        } while(false);

    }


    /**
     * Provided json body is not formatted as per api requirement.
     * @param string $message
     * @return mixed
     */
    public function respondWrongFormat($message = "JSON data is not well formatted.")
    {
        return $this->setStatusCode(400)->respondWithError($message);
    }


    /**
     * Takes an array of user filters and converts it
     * into reversed transformed filters
     *
     * @param $filters
     * @param $transformer
     * @return array|mixed Filters already reversed
     */
    public function prepareFilters($filters, $transformer)
    {
        //change the search input data to use the actual db field names
        foreach ($filters as $key => &$filter) {

            //if "field" is an array, separate it in different filters
            if(is_array($filter['field']))
            {
                foreach($filter['field'] as $filterField)
                {
                    $filters[] = $filter;
                    end($filters);
                    $filters[key($filters)]['field'] = $filterField;

                    if (!$transformer->reverse($filterField)) {
                        break;
                    }

                    $filters[key($filters)]['field'] = $transformer->reverse($filterField);
                }

                unset($filters[$key]);

                break;
            }

            if (!$transformer->reverse($filter['field'])) {
                return null;
            }
            $filters[$key]['field'] = $transformer->reverse($filter['field']);
        }

        return $filters;

    }


    /**
     * General search
     * @param array $data POST data to apply to the search. Contains, search, pagination and response arrays.
     * @param $repository
     * @param $repository
     * @param $transformer
     * @return mixed
     */
    public function generalSearch($data = array(), $repository, $transformer)
    {
        $data = $data->json()->all();

        if (array_key_exists('search', $data))
        {
            $filters = $this->prepareFilters($data['search'], $transformer);
            if(null == $filters){
                return $this->respondWrongFormat("The provided field wasn't found.");
            }
        }else
            return $this->respondWrongFormat();

        if (array_key_exists('pagination', $data) && array_key_exists('limit', $data['pagination']))
        {
            $items = $repository->getFiltered([], $filters, $data['pagination']);
        }else

            $items = $repository->getFiltered([], $filters);

        $transformedItems = $transformer->transformCollection($items->all());

        return $this->respondWithPagination($items, [
            'data' => $transformedItems
        ]);

    }
}
