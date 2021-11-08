<?php

namespace App\Repository\App;

use App\Entity\App\AppMenu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AppMenu|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppMenu|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppMenu[]    findAll()
 * @method AppMenu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppMenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppMenu::class);
    }

    /**
     * @return mixed
     */
    public function menuModule()
    {
        return $this->createQueryBuilder('appMenu')
            ->andWhere('appMenu.appMenu IS NULL')
            ->andWhere('appMenu.flagVisible = 1')
            ->andWhere('appMenu.flagDelete = 0')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $appMenu
     * @return mixed
     */
    public function menuSubModule($appMenu)
    {
        return $this->createQueryBuilder('appMenu')
            ->join('appMenu.appMenu', 'appMenuFather')
            ->andWhere('appMenu.appMenu = :appMenuFather')
            ->andWhere('appMenu.flagVisible = 1')
            ->andWhere('appMenu.flagDelete = 0')
            ->setParameter('appMenuFather', $appMenu)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param $appMenu
     * @return mixed
     */
    public function menuAction($appMenu)
    {
        return $this->createQueryBuilder('appMenu')
            ->join('appMenu.appMenu', 'appMenuFather')
            ->andWhere('appMenu.appMenu = :appMenuFather')
            ->andWhere('appMenu.flagVisible = 0')
            ->andWhere('appMenu.flagDelete = 0')
            ->setParameter('appMenuFather', $appMenu)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $code
     * @return AppMenu|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function menuSpecific($code): ?AppMenu
    {
        return $this->createQueryBuilder('appMenu')
            ->andWhere('appMenu.code = :code')
            ->setParameter('code', $code)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    private function arrayMenu($item, $submodule = false): array
    {
        return [
            'id' => $item->getId(),
            'name' => $item->getNameProfile(),
            'icon' => $item->getIcon(),
            'route' => $item->getRoute(),
            'submodule' => $submodule
        ];
    }

    public function allMenu(): array
    {
        $appMenuModule = $this->menuModule();
        $menu   = [];
        $action = [];
        $actionSubmodule = [];
        foreach ($appMenuModule AS $i){
            #> SUBMENU
            $appMenuSubModule = $this->menuSubModule($i->getId());
            foreach ($appMenuSubModule AS $ii){
                #> SUBMENU
                $appMenuSubModule2 = $this->menuSubModule($ii->getId());
                foreach ($appMenuSubModule2 AS $iii){
                    #> SUBMENU
                    $appMenuSubModule3 = $this->menuSubModule($iii->getId());
                    foreach ($appMenuSubModule3 AS $iv){
                        $menu4[$iii->getId()][] = $this->arrayMenu($iv);
                    }
                    $submenu = isset($menu4[$iii->getId()]) ? $menu4[$iii->getId()] : false;
                    $menu3[$ii->getId()][] = $this->arrayMenu($iii, $submenu);

                    #> ACTIONMENU
                    $appMenuAction3 = $this->menuAction($iii->getId());
                    foreach ($appMenuAction3 AS $iv){
                        $actionSubmodule[$i->getId()][] = $this->arrayMenu($iv);
                    }

                }
                $submenu = isset($menu3[$ii->getId()]) ? $menu3[$ii->getId()] : false;
                $menu2[$i->getId()][] = $this->arrayMenu($ii, $submenu);

                #> ACTIONMENU
                $appMenuAction2 = $this->menuAction($ii->getId());
                foreach ($appMenuAction2 AS $iii){
                    $actionSubmodule[$i->getId()][] = $this->arrayMenu($iii);
                }
            }
            $submenu = isset($menu2[$i->getId()]) ? $menu2[$i->getId()] : false;
            $menu[] = $this->arrayMenu($i, $submenu);

            #> ACTIONMENU
            $appMenuAction = $this->menuAction($i->getId());
            foreach ($appMenuAction AS $ii){
                $actionSubmodule[$i->getId()][] = $this->arrayMenu($ii);
            }
            $submenuAction = isset($actionSubmodule[$i->getId()]) ? $actionSubmodule[$i->getId()] : false;
            $action[] = $this->arrayMenu($i, $submenuAction);
        }
        return [
            'menu' => $menu,
            'action' => $action
        ];
    }
}
