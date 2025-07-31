#!/bin/bash

echo "=== Docker環境の状態確認 ==="
echo ""

echo "1. 実行中のコンテナ:"
echo "------------------------"
docker-compose ps
echo ""

echo "2. Dockerイメージ (koikoi関連):"
echo "------------------------"
docker images | grep -E "(REPOSITORY|koikoi)"
echo ""

echo "3. Dockerボリューム:"
echo "------------------------"
docker volume ls | grep -E "(DRIVER|koikoi)"
echo ""

echo "4. Dockerネットワーク:"
echo "------------------------"
docker network ls | grep -E "(NETWORK|koikoi)"
echo ""

echo "5. ディスク使用量:"
echo "------------------------"
docker system df
echo ""

echo "6. 削除可能なリソース:"
echo "------------------------"
echo "- 停止中のコンテナ: $(docker ps -a -q -f status=exited | wc -l)個"
echo "- 未使用のイメージ: $(docker images -f "dangling=true" -q | wc -l)個"
echo "- 未使用のボリューム: $(docker volume ls -qf dangling=true | wc -l)個"
echo ""

echo "7. Laravel環境の確認:"
echo "------------------------"
docker-compose exec -T php bash -c "
    echo 'PHP Version:' && php -v | head -n 1
    echo 'Laravel Version:' && php artisan --version 2>/dev/null || echo 'Laravel not installed'
    echo 'Composer Version:' && composer --version 2>/dev/null || echo 'Composer not installed'
    echo 'Node Version:' && node --version 2>/dev/null || echo 'Node not installed'
    echo 'NPM Version:' && npm --version 2>/dev/null || echo 'NPM not installed'
" 2>/dev/null || echo "PHPコンテナが起動していません"