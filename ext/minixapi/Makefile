copy-deps:
	rm -rf ext/TinCanPHP
	rsync -r --exclude .git --exclude .gitignore submodule/TinCanPHP/ ext/TinCanPHP

link-deps:
	rm -rf ext/TinCanPHP
	cd ext; ln -s ../submodule/TinCanPHP TinCanPHP

docs:
	git checkout master
	apigen generate -q -d doc -s MiniXapi.php
	git checkout gh-pages
	git add doc
	git commit --allow-empty -m "update doc"
	git push
	git checkout master