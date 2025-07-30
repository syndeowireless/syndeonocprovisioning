<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ip extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ips';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_usable_ip',
        'last_usable_ip',
        'in_use',
        'network_range',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'in_use' => 'boolean',
    ];

    /**
     * Scope to get available IP ranges (not in use).
     */
    public function scopeAvailable($query)
    {
        return $query->where('in_use', false);
    }

    /**
     * Scope to get IP ranges that are in use.
     */
    public function scopeInUse($query)
    {
        return $query->where('in_use', true);
    }

    /**
     * Mark this IP range as in use.
     */
    public function markAsInUse()
    {
        $this->update(['in_use' => true]);
    }

    /**
     * Mark this IP range as available.
     */
    public function markAsAvailable()
    {
        $this->update(['in_use' => false]);
    }

    /**
     * Get the network management entries that use this IP range.
     */
    public function networkManagements()
    {
        return $this->hasMany(NetworkManagement::class, 'allocated_ip_id');
    }
}

