if [ migrations/ ]; then
    rm -rf migrations/*;
    echo "migrations directory purged";
else
    mkdir migrations;
    echo "migrations directory created";
fi

php bin/console d:d:d --force;
php bin/console d:d:c;
php bin/console make:migration;
php bin/console d:m:m;
php bin/console d:f:l;

echo "Bravo, la database a été réinitialisée"