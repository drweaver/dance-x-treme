mkdir -p dist
for i in index classes venues events gallery contact 404; do \
  php $i.php > dist/$i.html; 
done
cd dist
cp -r ../img .
cp -r ../js .
cp ../favicon.ico .
