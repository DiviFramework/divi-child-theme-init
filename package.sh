plugin_basename=$(basename $(pwd))

#clean up
rm -rf /tmp/$plugin_basename || true;
rm /tmp/$plugin_basename.zip || true;

mkdir -p /tmp/$plugin_basename;

cd ..;
cp -r $plugin_basename /tmp;

cd -;
cd /tmp;

zip -r9 $plugin_basename.zip $plugin_basename -x *.git* -x *.sh -x *.json -x *.github* -x *node_modules* -x *grunt* -x *gulpfile.js* -x *styles*> /dev/null;
rm -rf /tmp/$plugin_basename;

echo "Copied file to /tmp/$plugin_basename.zip"