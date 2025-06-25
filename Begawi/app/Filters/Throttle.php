<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Throttle\Throttler; 

class Throttle implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        /** @var Throttler $throttler */
        $throttler = service('throttler');

        $maxRequests = (isset($arguments[0]) && is_numeric($arguments[0])) ? (int) $arguments[0] : 10;
        $timeInterval = (isset($arguments[1]) && is_numeric($arguments[1])) ? (int) $arguments[1] : 60;

        $bucketName = md5($request->getIPAddress()); 

        if ($throttler->check($bucketName, $maxRequests, $timeInterval) === false) {
            session()->setFlashdata('error', 'Terlalu banyak percobaan. Silakan coba lagi beberapa saat lagi.');

            return redirect()->back();

        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {

    }
}