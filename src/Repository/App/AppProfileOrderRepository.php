<?php

namespace App\Repository\App;

use App\Entity\App\AppProfileOrder;
use App\Services\App\AppTools;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AppProfileOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppProfileOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppProfileOrder[]    findAll()
 * @method AppProfileOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppProfileOrderRepository extends ServiceEntityRepository
{
    private $appTools;
    public function __construct(ManagerRegistry $registry, AppTools $appTools)
    {
        parent::__construct($registry, AppProfileOrder::class);
        $this->appTools = $appTools;
    }

    /**
     * @return mixed
     */
    public function profileDetail($appProfile)
    {
        return $this->createQueryBuilder('apo')
            ->andWhere('apo.appProfile = :appProfile')
            ->setParameter('appProfile', $appProfile)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $appProfile
     * @return int|mixed|string
     */
    private function profileModule($appProfile)
    {
        return $this->createQueryBuilder('apo')
            ->andWhere('apo.appProfile = :appProfile')
            ->andWhere('apo.appMenuModule IS NOT NULL')
            ->andWhere('apo.appMenuSubModule IS NULL')
            ->andWhere('apo.appMenuSubModule2 IS NULL')
            ->andWhere('apo.appMenuSubModule3 IS NULL')
            ->setParameter('appProfile', $appProfile)
            ->orderBy('apo.orderProfile', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $appProfile
     * @param $appMenuModule
     * @return int|mixed|string
     */
    private function profileSubModule($appProfile, $appMenuModule)
    {
        return $this->createQueryBuilder('apo')
            ->andWhere('apo.appProfile = :appProfile')
            ->andWhere('apo.appMenuModule = :appMenuModule')
            ->andWhere('apo.appMenuSubModule IS NOT NULL')
            ->andWhere('apo.appMenuSubModule2 IS NULL')
            ->andWhere('apo.appMenuSubModule3 IS NULL')
            ->setParameters([
                'appProfile'=> $appProfile,
                'appMenuModule' => $appMenuModule
            ])
            ->orderBy('apo.orderProfile', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $appProfile
     * @param $appMenuModule
     * @param $appMenuModule2
     * @return int|mixed|string
     */
    private function profileSubModule2($appProfile, $appMenuModule, $appMenuModule2)
    {
        return $this->createQueryBuilder('apo')
            ->andWhere('apo.appProfile = :appProfile')
            ->andWhere('apo.appMenuModule = :appMenuModule')
            ->andWhere('apo.appMenuSubModule = :appMenuModule2')
            ->andWhere('apo.appMenuSubModule2 IS NOT NULL')
            ->andWhere('apo.appMenuSubModule3 IS NULL')
            ->setParameters([
                'appProfile'=> $appProfile,
                'appMenuModule' => $appMenuModule,
                'appMenuModule2'=> $appMenuModule2
            ])
            ->orderBy('apo.orderProfile', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $appProfile
     * @param $appMenuModule
     * @param $appMenuModule2
     * @param $appMenuModule3
     * @return int|mixed|string
     */
    private function profileSubModule3($appProfile, $appMenuModule, $appMenuModule2, $appMenuModule3)
    {
        return $this->createQueryBuilder('apo')
            ->andWhere('apo.appProfile = :appProfile')
            ->andWhere('apo.appMenuModule = :appMenuModule')
            ->andWhere('apo.appMenuSubModule = :appMenuModule2')
            ->andWhere('apo.appMenuSubModule2 = :appMenuModule3')
            ->andWhere('apo.appMenuSubModule3 IS NOT NULL')
            ->setParameters([
                'appProfile'=> $appProfile,
                'appMenuModule' => $appMenuModule,
                'appMenuModule2'=> $appMenuModule2,
                'appMenuModule3'=> $appMenuModule3
            ])
            ->orderBy('apo.orderProfile', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    private function arrayMenu($item, $submodule = false): array
    {
        if($item->getRoute()){
            $router = $this->appTools->pathToUrl($item->getRoute());
        } else {
            $router = 'javascript:void(0);';
        }

        return [
            'id' => $item->getId(),
            'name' => $item->getNameProfile(),
            'icon' => $item->getIcon(),
            'code' => $item->getCode(),
            'route' => $router,
            'submodule' => $submodule
        ];
    }

    public function menuUser($appProfile)
    {
        $menu   = [];
        $appDynamicMenu = $this->profileModule($appProfile);
        foreach ($appDynamicMenu AS $i){

            $appModule = $i->getAppMenuModule();
            $appDynamicMenuModule = $this->profileSubModule($appProfile, $appModule);
            foreach ($appDynamicMenuModule AS $ii){

                $appSubModule =  $ii->getAppMenuSubModule();
                $appDynamicMenuModule2 = $this->profileSubModule2($appProfile, $appModule, $appSubModule);
                foreach ($appDynamicMenuModule2 AS $iii){

                    $appSubModule2 =  $iii->getAppMenuSubModule2();
                    $appDynamicMenuModule3 = $this->profileSubModule3($appProfile, $appModule, $appSubModule, $appSubModule2);
                    foreach ($appDynamicMenuModule3 AS $iv){
                        $appSubModule3 =  $iv->getAppMenuSubModule3();
                        $menu4[$iii->getId()][] = $this->arrayMenu($appSubModule3);
                    }
                    $submenu = isset($menu4[$iii->getId()]) ? $menu4[$iii->getId()] : false;
                    $menu3[$ii->getId()][] = $this->arrayMenu($appSubModule2, $submenu);
                }
                $submenu = isset($menu3[$ii->getId()]) ? $menu3[$ii->getId()] : false;
                $menu2[$i->getId()][] = $this->arrayMenu($appSubModule, $submenu);
            }
            $submenu = isset($menu2[$i->getId()]) ? $menu2[$i->getId()] : false;
            $menu[] = $this->arrayMenu($appModule, $submenu);
        }

        return $menu;
    }
}
