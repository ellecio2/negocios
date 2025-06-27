<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingProcess extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'shipping_company_id',
        'user_id',
        'pedidosya_tracking',
        'shipping_tracking',
        'pedidosya_status',
        'shipping_status',
        'pickup_date',
        'estimated_branch_arrival',
        'estimated_delivery_date',
        'pedidosya_data',
        'shipping_data',
        'total_cost'
    ];

    /**
     * Los atributos que deben convertirse a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'pickup_date' => 'datetime',
        'estimated_branch_arrival' => 'datetime',
        'estimated_delivery_date' => 'datetime',
        'pedidosya_data' => 'array',
        'shipping_data' => 'array',
        'total_cost' => 'float',
    ];

    /**
     * Obtiene la orden asociada a este proceso de envío.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Obtiene la compañía de envío asociada a este proceso.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shippingCompany()
    {
        return $this->belongsTo(ShippingCompany::class);
    }

    /**
     * Obtiene el usuario asociado a este proceso de envío.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Verifica si la recogida por PedidosYa ha sido completada.
     *
     * @return bool
     */
    public function isPedidosYaCompleted()
    {
        return in_array($this->pedidosya_status, ['delivered', 'completed']);
    }

    /**
     * Verifica si el envío con la transportadora ha sido completado.
     *
     * @return bool
     */
    public function isShippingCompleted()
    {
        return in_array($this->shipping_status, ['delivered', 'completed']);
    }

    /**
     * Verifica si todo el proceso ha sido completado.
     *
     * @return bool
     */
    public function isCompleted()
    {
        return $this->isPedidosYaCompleted() && $this->isShippingCompleted();
    }

    /**
     * Obtiene el estado general del proceso de envío.
     *
     * @return string
     */
    public function getStatusAttribute()
    {
        if ($this->isCompleted()) {
            return 'completed';
        }
        
        if ($this->isPedidosYaCompleted() && !$this->isShippingCompleted()) {
            return 'in_transit';
        }
        
        if (!$this->isPedidosYaCompleted()) {
            return 'pickup_pending';
        }
        
        return 'processing';
    }

    /**
     * Actualiza el estado de la recogida de PedidosYa.
     *
     * @param string $status
     * @param array|null $data
     * @return bool
     */
    public function updatePedidosYaStatus($status, $data = null)
    {
        $this->pedidosya_status = $status;
        
        if ($data) {
            $this->pedidosya_data = json_encode($data);
        }
        
        return $this->save();
    }

    /**
     * Actualiza el estado del envío con la transportadora.
     *
     * @param string $status
     * @param array|null $data
     * @return bool
     */
    public function updateShippingStatus($status, $data = null)
    {
        $this->shipping_status = $status;
        
        if ($data) {
            $this->shipping_data = json_encode($data);
        }
        
        return $this->save();
    }

    /**
     * Calcula el tiempo estimado de entrega en días.
     *
     * @return int
     */
    public function getEstimatedDeliveryDaysAttribute()
    {
        if (!$this->estimated_delivery_date) {
            return 0;
        }
        
        $now = now();
        if ($now->gt($this->estimated_delivery_date)) {
            return 0;
        }
        
        return $now->diffInDays($this->estimated_delivery_date);
    }
}