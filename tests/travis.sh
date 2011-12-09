sudo apt-get -y install php5-dev php-pear
sudo pecl install mysqlnd_uh-alpha
echo "extension=mysqlnd_ud.so" >> `php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||"`

wget http://getcomposer.org/composer.phar 
php composer.phar install
