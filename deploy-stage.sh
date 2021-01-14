#!/bin/bash

rm -rf /data/stagewww.gill.or.kr
mkdir /data/stagewww.gill.or.kr
/bin/cp -Rf ./ /data/stagewww.gill.or.kr
cd /data/stagewww.gill.or.kr
git checkout origin/stage
export LINK_ORI_DIR="/nas_contents/myss/gill"
export LINK_TMP_DIR="/data/stagewww.gill.or.kr/data"
/data/gitlab-runner/bin/upload_link.sh