<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.05.18
 * Time: 12:31
 */

namespace App\Entity\Ads;

use Doctrine\ORM\EntityManagerInterface;
use Elasticsearch\ClientBuilder;

class AdRepository
{
    const INDEX = 'ad';

    private $em;
    private $esClient;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em = $em;
        $this->esClient = ClientBuilder::create()->build();
    }


    public function save(AbstractAd $ad)
    {
        /*$existedAd = $this->em->getRepository(get_class($ad))->findOneBy(['site' => $ad->getSite(), 'site_id' => $ad->getSiteId()]);
        if($existedAd === null)
        {
            $existedAd = $ad;
        }*/

        $this->em->persist($ad);
        $this->em->flush();

        $data = [
            'index' => self::INDEX,
            'type' => $ad::getEsType(),
            'id' => $ad->getId(),
            'body' => $ad->toEsArray(),
        ];

        print_r($data);//die();

        $this->esClient->index($data);
    }
}