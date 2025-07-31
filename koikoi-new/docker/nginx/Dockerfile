FROM nginx:alpine

# Nginxの設定ファイルをコピー
COPY nginx.conf /etc/nginx/conf.d/default.conf

# ログディレクトリの作成
RUN mkdir -p /var/log/nginx

# 実行ユーザーの設定
RUN chown -R nginx:nginx /var/log/nginx

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]