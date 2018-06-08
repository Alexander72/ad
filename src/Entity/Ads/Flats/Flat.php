<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.05.18
 * Time: 15:56
 */

namespace App\Entity\Ads\Flats;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Flat
 * @ORM\Entity
 * @ORM\Table(name="flat")
 * @ORM\Table(
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="site_id_unique", columns={"site", "site_id"})
 *    }
 * )
 */
class Flat extends AbstractFlatAd
{
}