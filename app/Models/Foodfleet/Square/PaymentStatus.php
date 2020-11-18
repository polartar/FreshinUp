<?php

namespace App\Models\Foodfleet\Square;

use App\Models\Model;

/**
 * Class Status
 * @package App\Models\Foodfleet\Document\Template
 *
 * @property int id
 * @property string name
 *
 *
 * @property Payment[] payments
 */

class PaymentStatus extends Model
{
    protected $table = 'payment_statuses';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function payments()
    {
        return $this->hasMany(Payment::class, 'status_id', 'id');
    }
}
