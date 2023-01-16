Installation notes:

1. Unpack zip
2. Copy .env-dist to .env
3. Check and change parameters in .env if needed
4. Build docker image, create and run containers

   docker-compose build
   
   docker-compose up
   
5. Update your 'hosts' file by adding corresponding records for FE_HOST and BE_HOST params.

   Example: 
	127.0.0.1 fe.test.com
	127.0.0.1 be.test.com 		
	
6.Now you can check backend entering http://be.test.com:8888 in your browser.
  Admin credentials: 
	login:		admin@test.com
	password:	123456 
  Manager credentials: 
	login:		manager@test.com
	password:	123456 

7.You can check frontend entering http://fe.test.com:8888 in your browser.
	 
8.You can view dd using phpmyadmin entering http://localhost:8889 in your browser.
					