<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Vol;
use App\Entity\Tache;
use App\Entity\Avion;
use App\Entity\Aeroport;
use App\Entity\Tve;
use App\Entity\Employe;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manger)
    {
        gc_collect_cycles();
        $faker = \Faker\Factory::create('fr_FR');

       for($i =1 ; $i <= 2; $i++){

            $aeroport1=new Aeroport();
            $aeroport1->setNomAeroport($faker->name());
            $manger->persist($aeroport1);

            $aeroport2=new Aeroport();
            $aeroport2->setNomAeroport($faker->name());
            $manger->persist($aeroport2);

            $avion=new Avion();
            $avion->setCodeAvion($faker->sentence(5));
            $avion->setTypeAvion($faker->sentence(5));
            $manger->persist($avion);

           

            for( $j = 1 ; $i <= 2; $j++){

                $vol=new Vol();
                $vol->setNumVol($faker->randomNumber());
                $vol->setRetard($faker->biasedNumberBetween($min = 1, $max = 2));
                $vol->setIntervention($faker->biasedNumberBetween($min = 1, $max = 2));
                $vol->setDateArrivee($faker->dateTimeBetween($startDate = '-3 years', $endDate = 'now'));
                $aux1=$vol->getDateArrivee();
                $vol->setDateDepart($faker->dateTimeBetween($startDate = $aux1 , $endDate = 'now'));
                $vol->setAeroportArrivee($aeroport1);
                $vol->setAeroportDepart($aeroport2);
                $time1=$faker->time();
                $time2= date_create_from_format('H:i:s', $time1);
                $vol->setDureeAuSol($time2);
                $manger->persist($vol);



                for( $k = 1 ; $k <= 2; $k++){
                    $tache=new Tache();
                    $tache->setDescription($faker->sentence(5));
                    $manger->persist($tache);

                    $employe=new Employe();
                    $employe->setCin($faker->biasedNumberBetween($min = 9, $max = 29));
                    $date1=$faker->date($format = 'Y-m-d', $max = 'now');
                    $date2=date_create_from_format('Y-m-d', $date1);
                    $employe->setDateDeNaissance($date2);
                    $employe->setNomEmploye($faker->name());
                    $employe->setTypeEmploye($faker->biasedNumberBetween($min = 1, $max = 2));
                    $employe->setPhoto($faker->imageUrl());
                    $employe->setLogin($faker->userName());
                    $employe->setMotDepasse($faker->password());
                    $manger->persist($employe);


                    $tve=new Tve();
                    $tve->setEmploye($employe);
                    $tve->setTache($tache);
                    $tve->setVol($vol);
                    $tve->setDateDebutTache($faker->dateTimeBetween($startDate = $vol->getDateArrivee() , $endDate = $vol->getDateDepart()));
                    $tve->setDateFinTache($faker->dateTimeBetween($startDate = $tve->getDateDebutTache() , $endDate = $vol->getDateDepart()));
                    $manger->persist($tve);






                }





            }






       }


        $manager->flush();
    }
}
