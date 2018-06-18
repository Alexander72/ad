<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.05.18
 * Time: 12:31
 */

namespace App\Entity\Ads;

use App\Entity\Ads\Flats\Flat;
use App\Services\AdCache;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Elasticsearch\ClientBuilder;

class AdRepository extends ServiceEntityRepository
{
    const INDEX = 'ad';

    const ES_HOST = 'elastic:9200';

    private $esClient;

    private $adCache;

    public function __construct(
    	ManagerRegistry $registry,
		AdCache $adCache
    ) {
        parent::__construct($registry, Flat::class);
        $this->esClient = ClientBuilder::create()->setHosts([self::ES_HOST])->build();
        $this->adCache = $adCache;
    }

    public function save(AbstractAd $ad)
    {
    	if(!$this->adCache->isDifferent($ad))
	    {
	    	return;
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