<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterUserTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $client->request('GET', '/inscription');

        $client-> submitForm('Valider', [
            'register_user[firstname]' => 'Belka',
            'register_user[lastname]' => 'Bak',
            'register_user[email]' => 'belka@hotmail.fr',
            'register_user[plainPassword][first]' => '1234',
            'register_user[plainPassword][second]' =>  '1234' 
        ]);
        $this->assertResponseRedirects('/connexion');
        $client->followRedirect();

        $this->assertSelectorExists('div:contains("Votre compte est correctement créé, veuillez vous connecter.")');
    }
}
