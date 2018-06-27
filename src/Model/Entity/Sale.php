<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Sale Entity
 *
 * @property int $id
 * @property int $store_id
 * @property int $quantity
 * @property int $goal
 * @property string $month
 *
 * @property \App\Model\Entity\Store $store
 */
class Sale extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'store_id' => true,
        'quantity' => true,
        'goal' => true,
        'month' => true,
        'store' => true
    ];
}
