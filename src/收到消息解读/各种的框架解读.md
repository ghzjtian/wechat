#各种框架
>理解 composer.json 中的各种框架.


####[1.理解 monolog 框架](#monolog_usage)
####[2.Symfony框架](#symfony)
####[3.silexphp/Pimple](#pimple)
***
***
***


##1.理解 monolog 的使用<a name="monolog_usage"/>
>https://github.com/Seldaek/monolog
>https://www.jianshu.com/p/e2d7eb49746f

* 1.在项目下新建一个文件夹,然后在终端 cd 到该文件夹，运行

```
tiandeMacBook-Pro-2:testComposer tianzeng$ composer require monolog/monolog
Using version ^1.23 for monolog/monolog
./composer.json has been created
Loading composer repositories with package information
Updating dependencies (including require-dev)
Package operations: 2 installs, 0 updates, 0 removals
- Installing psr/log (1.0.2): Loading from cache
- Installing monolog/monolog (1.23.0): Loading from cache
monolog/monolog suggests installing aws/aws-sdk-php (Allow sending log messages to AWS services like DynamoDB)
monolog/monolog suggests installing doctrine/couchdb (Allow sending log messages to a CouchDB server)
monolog/monolog suggests installing ext-amqp (Allow sending log messages to an AMQP server (1.0+ required))
monolog/monolog suggests installing ext-mongo (Allow sending log messages to a MongoDB server)
monolog/monolog suggests installing graylog2/gelf-php (Allow sending log messages to a GrayLog2 server)
monolog/monolog suggests installing mongodb/mongodb (Allow sending log messages to a MongoDB server via PHP Driver)
monolog/monolog suggests installing php-amqplib/php-amqplib (Allow sending log messages to an AMQP server using php-amqplib)
monolog/monolog suggests installing php-console/php-console (Allow sending log messages to Google Chrome)
monolog/monolog suggests installing rollbar/rollbar (Allow sending log messages to Rollbar)
monolog/monolog suggests installing ruflin/elastica (Allow sending log messages to an Elastic Search server)
monolog/monolog suggests installing sentry/sentry (Allow sending log messages to a Sentry server)
Writing lock file
Generating autoload files

```

***

##2.Symfony框架<a name="symfony"/>
>各种的组件框架.
>https://github.com/symfony/symfony
>https://www.jianshu.com/p/72553681b71c
>视频: http://www.imooc.com/learn/244
>中文文档: http://www.symfonychina.com/


***

##3.silexphp/Pimple<a name="pimple"/>
>容器框架.
>https://github.com/silexphp/Pimple
>https://www.jianshu.com/p/394a40485ca5
>https://laravel-china.org/articles/4276/php-dependency-injection-container-pimple-notes









