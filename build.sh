sudo docker compose down
sudo rm -r mysql
sudo docker build . -t web
sudo docker compose up
