<?php



namespace App\Http\Resources\V2\Seller;



use Illuminate\Http\Resources\Json\JsonResource;



class SellerWithdrawResource extends JsonResource

{

    /**

     * Transform the resource into an array.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable

     */

    public function toArray($request)

    {

        $status = translate('Pagado');

        if($this->status == 0) {

            $status = translate('Pendiente');

        }

        return [

            'id' => $this->id,

            'amount' => format_price($this->amount),

            'status' =>  $status,

            'created_at' => date('d-m-Y', strtotime($this->created_at)),

        ];

    }

}

