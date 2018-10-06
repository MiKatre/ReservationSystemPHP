<?php

namespace App\Responder;

use App\Entity\Order;
use App\Utils\ErrorControl;
use App\Utils\PriceControl;
use Symfony\Component\HttpFoundation\JsonResponse;

class PaiementResponder
{
  private $price;
  private $errorControl;

  public function __construct(PriceControl $price, ErrorControl $errorControl){
    $this->price = $price;
    $this->errorControl = $errorControl;
  }

  /**
   * @param Order $order
   * @return JsonResponse
   */
  public function paidRes($result)
  {
    if ($result->error) {
      $response = $this->errorControl->error($result->status, $result->message);
    } else {
      $response = new JsonResponse(array(
        'paid' => $result->charge->paid,
        'email' => $result->charge->receipt_email,
        'message' => "Commande passée avec succès",
      ));
    }
    return $response;

  }
}

