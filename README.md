![Pipeline](https://i.imgur.com/uq0UsSR.png)

## Pipeline

A simple container designed for CI/CD.

### Usage

You can run this container as follow:
```
docker run -p 8000:80 jarvisanalytics/pipeline
```

### Operating System

This container is based on [Ubuntu 18.04](https://hub.docker.com/_/ubuntu)

### Installed Softwares

This container is built based on Ubuntu 18.04. And here are some of the built-in dependencies/apps installed.

 - Docker
 - AWS CLI
 - PHP 7.3
 - Composer
 - AWS IAM Authenticator
 - Node.js 12.x
 - Python 2.7.17


### Tool Scripts

Some scripts are built in as well.

#### JWT Token Generator

This is handy if you're dealing with JWT tokens. So, to generate a token, you can do this command:

```
tools jwt --issuer <issuer> --expire_in <in_days> --scopes <foo,bar> --client_id <client_id>
```
