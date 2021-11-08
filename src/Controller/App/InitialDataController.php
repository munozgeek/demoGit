<?php
namespace App\Controller\App;

use App\Entity\App\AppMenu;
use App\Entity\App\AppProfile;
use App\Entity\App\AppProfileOrder;
use App\Entity\App\AppUser;
use App\Services\App\AppTools;
use App\Tools\Data\AppSecurity;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/data_inicial")
 */
class InitialDataController extends AbstractController
{
    use AppSecurity;
    public $appTools;
    public function __construct(AppTools $appTools)
    {
        $this->appTools = $appTools;
    }

    /**
     * @Route("/menu", name="initial_data_menu", methods={"GET"})
     */
    public function menu(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $dataMenus = $this->DataMenu();
        foreach ($dataMenus as $i) {
            $appMenu = $em->getRepository(AppMenu::class)->menuSpecific($i['code']);
            if (!$appMenu) {
                $appMenuFather = $em->getRepository(AppMenu::class)->menuSpecific($i['fatherCode']);
                if (!$appMenuFather) {
                    $appMenuFather = null;
                }
                $appMenu = new AppMenu();
                $appMenu
                    ->setName($i['name'])
                    ->setNameProfile($i['longName'])
                    ->setCode($i['code'])
                    ->setRoute($i['route'])
                    ->setIcon($i['icon'])
                    ->setFlagVisible($i['flagVisibilidad'])
                    ->setFlagDelete(false)
                    ->setFlagStatus(true)
                    ->setAppMenu($appMenuFather)
                ;
                $em->persist($appMenu);
                $em->flush();
            } else {
                $appMenuFather = $em->getRepository(AppMenu::class)->menuSpecific($i['fatherCode']);
                if (!$appMenuFather) {
                    $appMenuFather = null;
                }
                $appMenu
                    ->setName($i['name'])
                    ->setNameProfile($i['longName'])
                    ->setCode($i['code'])
                    ->setRoute($i['route'])
                    ->setIcon($i['icon'])
                    ->setFlagVisible($i['flagVisibilidad'])
                    ->setFlagDelete(false)
                    ->setFlagStatus(true)
                    ->setAppMenu($appMenuFather)
                ;
                $em->persist($appMenu);
                $em->flush();
            }
        }
        return new JsonResponse(['name' => 'script de carga menu', 'status' => 'success'],200);
    }
    
    private function newAppProfileOrder($em, $appProfile, $order, $appMenuModule, $appMenuSubModule = null, $appMenuSubModule2 = null, $appMenuSubModule3 = null)
    {
        $appDynamicMenu = new AppProfileOrder();
        $appDynamicMenu
            ->setAppProfile($appProfile)
            ->setAppMenuModule($appMenuModule)
            ->setAppMenuSubModule($appMenuSubModule)
            ->setAppMenuSubModule2($appMenuSubModule2)
            ->setAppMenuSubModule3($appMenuSubModule3)
            ->setOrderProfile($order)
        ;
        $em->persist($appDynamicMenu);
        $em->flush();
    }

    private function newDetailProfile($em, $appProfile)
    {
        $appMenuModule = $em->getRepository(AppMenu::class)->menuModule();
        $nModulo=1;
        foreach ($appMenuModule AS $ii){

            $this->newAppProfileOrder($em, $appProfile, $nModulo, $ii);

            $appMenuSubModule = $em->getRepository(AppMenu::class)->menuSubModule($ii->getId());
            $nSubModulo=1;
            foreach ($appMenuSubModule AS $iii){

                $this->newAppProfileOrder($em, $appProfile, $nSubModulo, $ii, $iii);

                $appMenuSubModule2 = $em->getRepository(AppMenu::class)->menuSubModule($iii->getId());
                $nSubModulo2=1;
                foreach ($appMenuSubModule2 AS $iv){

                    $this->newAppProfileOrder($em, $appProfile, $nSubModulo2, $ii, $iii, $iv);

                    $appMenuSubModule3 = $em->getRepository(AppMenu::class)->menuSubModule($iv->getId());
                    $nSubModulo3=1;
                    foreach ($appMenuSubModule3 AS $v){

                        $this->newAppProfileOrder($em, $appProfile, $nSubModulo3, $ii, $iii, $iv, $v);

                        $nSubModulo3++;
                    }
                    $nSubModulo2++;
                }
                $nSubModulo++;
            }
            $nModulo++;
        }
    }

    /**
     * @Route("/profile", name="initial_data_profile", methods={"GET"})
     * @throws NonUniqueResultException
     */
    public function profile()
    {
        $em = $this->getDoctrine()->getManager();
        $profiles = $this->DataProfile();

        foreach ($profiles as $i) {
            $appProfile = $em->getRepository(AppProfile::class)->code($i['code']);

            if (!$appProfile) {
                $appProfile = new AppProfile();
                $appProfile
                    ->setCode($i['code'])
                    ->setName($i['name'])
                    ->setDescription($i['description'])
                    ->setFlagDelete(false)
                    ->setFlagStatus(true)
                ;
                $em->persist($appProfile);
                $em->flush();

                if ($i['detail'] == 'All') {
                    $appMenu = $em->getRepository(AppMenu::class)->findAll();
                    foreach ($appMenu AS $ii) {
                        $appProfile->addAppMenu($ii);
                    }

                    $em->persist($appProfile);
                    $em->flush();

                    $this->newDetailProfile($em, $appProfile);
                }
            } else {

                $appProfile
                    ->setCode($i['code'])
                    ->setName($i['name'])
                    ->setDescription($i['description'])
                    ->setFlagDelete(false)
                    ->setFlagStatus(true)
                ;
                $em->persist($appProfile);
                $em->flush();
                if ($i['detail'] == 'All') {

                    $appMenu = $em->getRepository(AppMenu::class)->findAll();
                    foreach ($appMenu AS $ii) {
                        $appProfile->removeAppMenu($ii);
                    }
                    $em->flush();

                    $appMenu = $em->getRepository(AppMenu::class)->findAll();
                    foreach ($appMenu AS $ii) {
                        $appProfile->addAppMenu($ii);
                    }
                    $em->persist($appProfile);
                    $em->flush();

                    $appDynamicMenu = $em->getRepository(AppProfileOrder::class)->profileDetail($appProfile);
                    foreach ($appDynamicMenu AS $ii){
                        $em->remove($ii);
                    }
                    $em->flush();


                    $this->newDetailProfile($em, $appProfile);
                }
            }
        }

        return new JsonResponse(['name' => 'script de carga profile', 'status' => 'success'],200);
    }

    /**
     * @Route("/users", name="initial_data_users", methods={"GET"})
     * @throws NonUniqueResultException
     */
    public function users(UserPasswordHasherInterface $encoder, MailerInterface $mailer)
    {
        $em = $this->getDoctrine()->getManager();
        $profiles = $this->DataUser();

        foreach ($profiles as $i) {
            $appUser = $em->getRepository(AppUser::class)->loadUserByUsername($i['email']);
            $appProfile = $em->getRepository(AppProfile::class)->code($i['profile']);
            if(!$appUser) {
                $appUser = new AppUser();
                $appUser
                    ->setCreationDate(new \DateTime())
                    ->setName($i['name'])
                    ->setSurname($i['surname'])
                    ->setEmail($i['email'])
                    ->setSalt(md5(time()))
                    ->setFlagAccess(true)
                    ->setFlagStatus(true)
                    ->setAppProfile($appProfile)
                ;
                $encoded = $encoder->hashPassword($appUser, $i['password']);
                $appUser->setPassword($encoded);
            } else {
                $appUser
                    ->setName($i['name'])
                    ->setSurname($i['surname'])
                    ->setEmail($i['email'])
                    ->setSalt(md5(time()))
                    ->setFlagAccess(true)
                    ->setFlagStatus(true)
                    ->setAppProfile($appProfile)
                ;
                $encoded = $encoder->hashPassword($appUser, $i['password']);
                $appUser->setPassword($encoded);
            }
            $em->persist($appUser);
            $em->flush();
            $this->appTools->sendMail($mailer, $appUser, $i['password']);
        }

        return new JsonResponse(['name' => 'script de carga profile', 'status' => 'success'],200);
    }
}