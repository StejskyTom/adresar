<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use App\Services\StringService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $names = array('Petr', 'Honza', 'Pavel', 'Karel', 'Filip', 'Franta', 'Marek', 'Premek', 'Tomas', 'Jakub', 'Adam', 'Lukas');
        $surnames = array('Novotny', 'Novy', 'Skocdopole', 'Skokan', 'Brzobohaty', 'Stejskal', 'Boháček', 'Zacek', 'Svoboda', 'Cerny', 'Prochazka', 'Vesely');

        for ($i = 0; $i < 12; $i++) {
            $contact = new Contact();
            $contact->setName($names[$i]);
            $contact->setSurname($surnames[$i]);
            $contact->setPhone('123456789');
            $contact->setEmail($surnames[$i].'@'.$names[$i].'.cz');
            $contact->setNote('Poznámka pro daný kontakt '.$contact->getName());

            $manager->persist($contact);
            $contacts[] = $contact;
        }
        $manager->flush();

        foreach ($contacts as $con) {
            $identifier = StringService::getSluggedString($con->getSurname().'-'.$con->getId());
            $con->setIdentifier($identifier);

            $manager->persist($con);
        }
        $manager->flush();
    }
}
