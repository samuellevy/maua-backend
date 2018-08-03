<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Course Entity
 *
 * @property int $id
 * @property string $title
 * @property string $subtitle
 * @property string $description
 * @property string $video_url
 * @property int $active
 * @property int $right
 *
 * @property \App\Model\Entity\Question[] $questions
 * @property \App\Model\Entity\Quiz[] $quiz
 */
class Course extends Entity
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
        'title' => true,
        'subtitle' => true,
        'description' => true,
        'video_url' => true,
        'active' => true,
        'right' => true,
        'questions' => true,
        'thumb_url' => true,
        'movie_url' => true,
        'quiz' => true
    ];
}
