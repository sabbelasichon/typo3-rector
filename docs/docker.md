# How to run TYPO3 Rector with Docker

You can run TYPO3 Rector on your project using our official Docker Image.
We publish each version as a Docker image on GitHub Container Registry and Docker Hub.
You can use either `ghcr.io/sabbelasichon/typo3-rector` or `schreiberten/typo3-rector`.
See:
*  https://github.com/users/sabbelasichon/packages/container/package/typo3-rector
*  https://hub.docker.com/r/schreiberten/typo3-rector

The `latest` tag represents the latest tagged version.
The `dev-master` tag is the current development snapshot of the master branch.
Use an exact version tag like `0.8.16` to run with a specific version.

*Note that TYPO3 Rector inside the Docker container expects your application in `/app` directory - it is mounted via volume from the current directory (`$PWD`) in the following examples.*

## Run TYPO3 Rector with Docker local
Best practise is to run TYPO3 Rector in the container as the current user rather than root to prevent permission issues on your host filesystem:
```shell
docker run --rm \
  --volume $PWD:/app \
  --user $(id -u):$(id -g) \
  ghcr.io/sabbelasichon/typo3-rector typo3-init
```

If you want to be able to run `typo3-rector` as if it was installed on your host locally, you can define the following function in your `~/.bashrc`, `~/.zshrc` or similar:

```shell
typo3-rector () {
    tty=
    test -t 0 && tty=--tty
    docker run \
        $tty \
        --interactive \
        --rm \
        --user $(id -u):$(id -g) \
        --volume $PWD:/app \
        ghcr.io/sabbelasichon/typo3-rector "$@"
}
```
