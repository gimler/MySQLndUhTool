php -i

wget http://pecl.php.net/get/mysqlnd_uh-0.1.0a1.tgz
tar -xzf mysqlnd_uh-0.1.0a1.tgz
sh -c "cd memcache-0.1.0a1 && phpize && ./configure && make && sudo make install"

#sudo apt-get -y install php5-dev php-pear
#sudo pecl install mysqlnd_uh-alpha

echo "extension=myslnd_uh.so" >> `php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||"`

wget http://getcomposer.org/composer.phar 
php composer.phar install
