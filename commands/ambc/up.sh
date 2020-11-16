#!/usr/bin/env bash

docker-compose up -d --force-recreate

# Start Node Exporter to collect OS Metrics and Network Information
docker run --name="ambc_node_exporter" \
  -d \
  --net="host" \
  --pid="host" \
  -v "/:/host:ro,rslave" \
  quay.io/prometheus/node-exporter \
  --path.rootfs=/host