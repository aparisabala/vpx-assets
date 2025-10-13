<?php

namespace App\Traits\PxTraits\Dependancy;

use Illuminate\Support\Facades\Lang;
use Response;

trait ResponseDependacy
{
    public function logRequest($type="POST")
    {   
        switch ($type) {
            case 'FILE':
                print_r($_FILES);
                break;
            case 'GET':
                    print_r($_GET);
                    break;
            default:
                print_r($_POST);
                break;
        }
        die();
    }
    public function response($params = [])
    {
        if (isset($params['type'])) {
            switch ($params['type']) {
                case 'noUpdate':
                    return $this->noUpdate($params);
                    break;
                case 'wrong':
                    return $this->wentWrong($params);
                    break;
                case 'success':
                    return $this->success($params);
                    break;
                case 'bigError':
                    return $this->bigErrors($params);
                    break;
                case 'noData':
                    return $this->noData($params);
                    break;
                case 'validation':
                    return $this->validation($params);
                    break;
                case 'load_html':
                    return $this->loadHtml($params);
                    break;
                    
                default:
                    return $this->noResponse();
                    break;
            }
        } else {

            return $this->noResponse();
        }
    }

    protected function noResponse()
    {
        return Response::json(array(
            "success" => false,
            "noUpdate" => true,
            "title" => '<span class="green fs-14">' . Lang::get('common.no_message_return') . '</span>',
            "content" => '',
            "mobMgs" => "No Response Defined",
            "mobDes" => ""
        ));
    }

    protected function noUpdate($params)
    {
        $title = isset($params['title']) ? $params['title'] : "";
        $content = isset($params['content']) ? $params['content'] : "";
        return Response::json(array(
            "success" => false,
            "noUpdate" => true,
            "title" => '<span class="required fs-14">' . $title . '</span>',
            "content" => '<span class="required fs-14">' . $content . '</span>',
            "mobMgs" => strip_tags($title),
            "mobDes" => strip_tags($content),
            "data" => isset($params['data']) ? collect($params['data']) : null
        ));
    }

    protected function success($params)
    {
        return Response::json(array(
            "success" => true,
            "data" => (isset($params['data'])) ? $params['data'] : []
        ));
    }

    protected function validation($params)
    {
        return Response::json(array(
            "success" => false,
            "errors" => (isset($params['errors'])) ? $params['errors'] : []
        ));
    }

    protected function wentWrong($params)
    {
        $lang = (isset($params['lang'])) ? Lang::get('common.' . $params['lang'])  : Lang::get('common.went_wrong');
        return Response::json(array(
            "success" => false,
            "noUpdate" => true,
            "title" => '<span class="required fs-14">' . $lang . '</span>',
            "content" => '',
            "mobMgs" => $lang,
            "mobDes" => ""
        ));
    }

    protected function bigErrors($params)
    {
        return Response::json(array(
            "success"   => false,
            "bigError"  => true,
            "bigErrors" => (isset($params['errors'])) ? $params['errors'] : [],
        ));
    }

    protected function noData($params)
    {
        $image = (isset($params['image'])) ? $params['image'] : "no";
        $title = (isset($params['title'])) ? $params['title'] : "";
        $message = (isset($params['message'])) ? $params['message'] : "";
        $url = (isset($params['url'])) ? $params['url'] : "no";
        $btn_text = (isset($params['btn_text'])) ? $params['btn_text'] : "";
        return view(
            'common.view.no_data_found',
            [
                'image' => $image,
                'title' => $title,
                'message' => $message,
                'url' => $url,
                'btn_text' => $btn_text
            ]
        );
    }
    protected function loadHtml($params)
    {
        return Response::json(array(
            "success"   => true,
            "data" => isset($params['data']) ? collect($params['data']) : []
        ));
    }
    
}
