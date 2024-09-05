sed -i 's/^        Options Indexes FollowSymLinks/        Options -Indexes -FollowSymLinks/' /etc/apache2/apache2.conf
sed -i 's/^ServerTokens Full/ServerTokens Prod/' /etc/apache2/conf-available/security.conf
sed -i 's/^ServerSignature On/ServerSignature Off/' /etc/apache2/conf-available/security.conf
