<?php


namespace App\client;


use Symfony\Component\Ldap\Entry;
use Symfony\Component\Ldap\Ldap;

class LdapClient
{

    /**
     * @var string
     */
    private $ldapUri;
    /**
     * @var string
     */
    private $ldapAdminDn;
    /**
     * @var string
     */
    private $ldapAdminPassword;

    private $connection;

    public function __construct(string $ldapUri, string $ldapAdminDn, string $ldapAdminPassword)
    {
        $this->ldapUri = $ldapUri;
        $this->ldapAdminDn = $ldapAdminDn;
        $this->ldapAdminPassword = $ldapAdminPassword;
    }

    private function getConnection()
    {
        if (!$this->connection){
            $this->connection = Ldap::create('ext_ldap', [
                'connection_string' => $this->ldapUri,
            ]);

            $this->connection->bind($this->ldapAdminDn, $this->ldapAdminPassword);
        }

        return $this->connection;
    }

    public function demo()
    {
        $ldap = $this->getConnection();
        $entryManager = $ldap->getEntryManager();

        $entry = new Entry('', ['uid=monIdentifiant, ou=trainer, dc=example, dc=nom',
            'cn' => 'Nom Prenom',
            'mail' => 'example@example.com'
        ]);

        $entryManager->add($entry);
    }

    public function demo2()
    {
        $ldap = $this->getConnection();

        $query = $ldap->query('dc=example,dc=com', '(&(objectclass=person)(ou=Maintainers))');
        $results = $query->execute();

        foreach ($results as $entry) {
            dump($entry->getAttribute('mail'));
        }
    }

}