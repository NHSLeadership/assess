# Assess Dockerfile

This Dockerfile has been developed in a very opinionated way to run Assess in the NHS Leadership Academy's Kubernetes-based infrastructure. We make no guarantee and provide no support for running this container image outside of our environment.

The base images on which this one is derived from can be found at [nhsl-docker-base](https://github.com/NHSLeadership/nhsl-docker-base/) along with various environment variables and configuration files.

Assumptions made:

1. Atatus is our APM of choice, it is baked into our base image
2. All secrets are injected via the Kubernetes External Secrets operator
3. We run our containers in a rootless, readOnlyFilesystem environment
4. Our containers rely on a Redis-like memory store. At time of writing we've standardised on DragonflyDB
5. Openresty (Nginx fork) and PHP have been decoupled, running different containers in the same pod
6. A worker container is run seperately from PHP-FPM to handle things such as Laravel queues and schedules

There is no reason why the containers shouldn't run with these caveats in mind, nor any reason why the Assess application couldn't run in a different container environment given the correct PHP version and dependencies are installed.