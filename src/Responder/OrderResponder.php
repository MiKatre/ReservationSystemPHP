<?php

namespace App\Responder;

use App\Entity\Order;
use App\Utils\PriceControl;
use Symfony\Component\HttpFoundation\JsonResponse;

class OrderResponder
{
  private $price;

  public function __construct(PriceControl $price){
    $this->price = $price;
  }

  /**
   * @param $result
   * @return JsonResponse
   */
  public function createOrderRes($result)
  {
    if ($result->error)
      $response = new JsonResponse(array('errors' => $result->errorsArray));
    else
      $response = new JsonResponse(array(
        'success' => true,
        'message' =>  $result->message,
      ));
    return $response;
  }
}

