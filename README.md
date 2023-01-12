## Creating an instance of mics.tools platform

The MICS platform has been developed using the Laravel web application framework, details of which can be found at the end of this document. The following steps describe how to install and setup the software, services and code required to instantiate an instance of the platform locally. Github can then be used in the normal manner to branch the repository and pull and push commits as deemed fit!

For more information regarding the use and sharing of this code, contact Earthwatch Europe at jsprinks@earthwatch.org.

## Software to install

- A GitHub GUI client, such as [Smartgit](https://www.syntevo.com/smartgit/), for easy pulling and pushing of platform code and amendments.
- A code editor for editing, such as [Sublime Text](https://www.sublimetext.com/) or [VS code](https://code.visualstudio.com/).
- A vector graphics editor is optional, for editing the graphics and imagery of MICS. [Inkscape](https://inkscape.org/) is an open-source example.

## Setting up a local server to host the platform

Firstly, you will need to install a windows web development environment, we recommend [wampserver](https://www.wampserver.com/en/). After installation, clone the mics.tools repository to C:\wamp\www\mics.local, using Smartgit or your preferred method.

To configure your server:

 - Open a browser (the one you set as default when installing wampserver), and go to the url 'localhost'.
 - Select 'Add a virtual host' in the bottom left-hand corner of the screen.
 - You should see this screen (or very similar):

![image](https://user-images.githubusercontent.com/80452185/212075801-2e7ea39d-e2db-4b1b-804f-a5bb48200e83.png)

 - Configure as follows: Set the name as <b>mics.local</b>; set the path as <b>c:\wamp\www\mics.local\public</b>; select the checkbox for PHP mode and set to 8.0.26 in the drop down menu; and finally there is no need to set an IP adress, so leave blank.
 


__________________________________________________________________________________________________________________________________________________________________

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
