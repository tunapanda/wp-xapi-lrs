copy-deps:
	rm -rf ext/minixapi
	rsync -r --exclude .git submodule/minixapi/ ext/minixapi

link-deps:
	rm -rf ext/minixapi
	cd ext; ln -s ../submodule/minixapi minixapi

readme:
	wp2md convert readme.txt README.md