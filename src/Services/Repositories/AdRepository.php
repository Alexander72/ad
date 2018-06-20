<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.05.18
 * Time: 12:31
 */

namespace App\Services\Repositories;

use App\Entity\Ads\AbstractAd;
use App\Entity\Ads\Flat;
use App\Services\AdCache;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Elasticsearch\ClientBuilder;

class AdRepository extends ServiceEntityRepository
{
    const INDEX = 'ad';

    private $esClient;

    private $adCache;

    public function __construct(
    	ManagerRegistry $registry,
		AdCache $adCache
    ) {
        parent::__construct($registry, Flat::class);
        $this->esClient = ClientBuilder::create()->setHosts([$_ENV['ELASTIC_HOST'].':'.$_ENV['ELASTIC_PORT']])->build();
        $this->adCache = $adCache;
    }

    public function save(AbstractAd $ad)
    {
    	if(!$this->adCache->isDifferent($ad))
	    {
	    	return;
	    }

	    $existedFlat = $this->findOneBy(['site' => $ad->getSite(), 'siteId' => $ad->getSiteId()]);
	    if($existedFlat)
	    {
		    $adEsArray = $ad->toArray();
		    if($adEsArray == $existedFlat->toArray())
		    {
			    return;
		    }

		    $existedFlat->fromArray($adEsArray);
		    $ad = $existedFlat;
	    }

	    $this->_em->persist($ad);
        $this->_em->flush();

        $data = [
            'index' => self::INDEX,
            'type' => $ad::getEsType(),
            'id' => $ad->getId(),
            'body' => $ad->toEsArray(),
        ];

        $this->esClient->index($data);

        $this->adCache->put($ad);
    }
}