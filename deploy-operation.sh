#!/bin/bash

rm -rf /data/gill.or.kr
mkdir /data/gill.or.kr
/bin/cp -Rf ./ /data/gill.or.kr
cd /data/gill.or.kr
git checkout origin/master
export LINK_ORI_DIR="/nas_contents/myss/gill"
export LINK_TMP_DIR="/data/gill.or.kr/data"
/data/gitlab-runner/bin/upload_link.sh