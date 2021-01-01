<?php

namespace App;

use Carbon\Carbon;
use App\Models\Foodfleet\Company;
use App\Models\Foodfleet\Document;
use App\Models\Foodfleet\FinancialReport;
use App\Notifications\PasswordResetNotification;
use FreshinUp\FreshBusForms\Models\User\UserStatus;
use FreshinUp\FreshBusForms\Http\Resources\User\Level;

/**
 * Class User
 * @package App
 *
 * @property int id
 * @property string uuid
 * @property string first_name
 * @property string last_name
 * @property string email
 * @property string mobile_phone
 * @property string office_phone
 * @property string notes
 * @property string password
 * @property string remember_token
 * @property string requested_company
 * @property string title
 * @property Carbon email_verified_at
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon deleted_at
 * @property Carbon last_login
 * @property Carbon company_join_date
 * @property int status
 * @property int level
 * @property int type
 * @property int company_id
 * @property string data_visibility // json
 * @property int company_branch_id
 * @property string manager_uuid
 *
 *
 * @property UserStatus user_status
 * @property Level user_level
 * @property Company company
 * @property User manager
 * // and so on ...
 */
class User extends \FreshinUp\FreshBusForms\Models\User\User
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data_visibility' => 'array'
    ];

    public function financialReports()
    {
        return $this->hasMany(FinancialReport::class);
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'assigned', 'assigned_type', 'assigned_uuid', 'uuid');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token));
    }

    public function manager ()
    {
        return $this->belongsTo(User::class, 'manager_uuid', 'uuid');
    }
}
