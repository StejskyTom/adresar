<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $names = array('Petr', 'Honza', 'Pavel', 'Karel', 'Filip', 'Franta', 'Marek', 'Přemek', 'Tomáš', 'Jakub', 'Adam', 'Lukáš');
        $surnames = array('Novotný', 'Nový', 'Skočdopole', 'Skokan', 'Brzobohatý', 'Stejskal', 'Boháček', 'Žáček', 'Svoboda', 'Černý', 'Procházka', 'Veselý');

        for ($i = 0; $i < 12; $i++) {
            $contact = new Contact();
            $contact->setName($names[$i]);
            $contact->setSurname($surnames[$i]);
            $contact->setEmail($surnames[$i].'@'.$names[$i].'.cz');

            $manager->persist($contact);
        }
        $manager->flush();
    }
}
