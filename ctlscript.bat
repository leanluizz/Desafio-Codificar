@echo off
rem START or STOP Services
rem ----------------------------------
rem Check if argument is STOP or START

if not ""%1"" == ""START"" goto stop

if exist C:\PHP\hypersonic\scripts\ctl.bat (start /MIN /B C:\PHP\server\hsql-sample-database\scripts\ctl.bat START)
if exist C:\PHP\ingres\scripts\ctl.bat (start /MIN /B C:\PHP\ingres\scripts\ctl.bat START)
if exist C:\PHP\mysql\scripts\ctl.bat (start /MIN /B C:\PHP\mysql\scripts\ctl.bat START)
if exist C:\PHP\postgresql\scripts\ctl.bat (start /MIN /B C:\PHP\postgresql\scripts\ctl.bat START)
if exist C:\PHP\apache\scripts\ctl.bat (start /MIN /B C:\PHP\apache\scripts\ctl.bat START)
if exist C:\PHP\openoffice\scripts\ctl.bat (start /MIN /B C:\PHP\openoffice\scripts\ctl.bat START)
if exist C:\PHP\apache-tomcat\scripts\ctl.bat (start /MIN /B C:\PHP\apache-tomcat\scripts\ctl.bat START)
if exist C:\PHP\resin\scripts\ctl.bat (start /MIN /B C:\PHP\resin\scripts\ctl.bat START)
if exist C:\PHP\jetty\scripts\ctl.bat (start /MIN /B C:\PHP\jetty\scripts\ctl.bat START)
if exist C:\PHP\subversion\scripts\ctl.bat (start /MIN /B C:\PHP\subversion\scripts\ctl.bat START)
rem RUBY_APPLICATION_START
if exist C:\PHP\lucene\scripts\ctl.bat (start /MIN /B C:\PHP\lucene\scripts\ctl.bat START)
if exist C:\PHP\third_application\scripts\ctl.bat (start /MIN /B C:\PHP\third_application\scripts\ctl.bat START)
goto end

:stop
echo "Stopping services ..."
if exist C:\PHP\third_application\scripts\ctl.bat (start /MIN /B C:\PHP\third_application\scripts\ctl.bat STOP)
if exist C:\PHP\lucene\scripts\ctl.bat (start /MIN /B C:\PHP\lucene\scripts\ctl.bat STOP)
rem RUBY_APPLICATION_STOP
if exist C:\PHP\subversion\scripts\ctl.bat (start /MIN /B C:\PHP\subversion\scripts\ctl.bat STOP)
if exist C:\PHP\jetty\scripts\ctl.bat (start /MIN /B C:\PHP\jetty\scripts\ctl.bat STOP)
if exist C:\PHP\hypersonic\scripts\ctl.bat (start /MIN /B C:\PHP\server\hsql-sample-database\scripts\ctl.bat STOP)
if exist C:\PHP\resin\scripts\ctl.bat (start /MIN /B C:\PHP\resin\scripts\ctl.bat STOP)
if exist C:\PHP\apache-tomcat\scripts\ctl.bat (start /MIN /B /WAIT C:\PHP\apache-tomcat\scripts\ctl.bat STOP)
if exist C:\PHP\openoffice\scripts\ctl.bat (start /MIN /B C:\PHP\openoffice\scripts\ctl.bat STOP)
if exist C:\PHP\apache\scripts\ctl.bat (start /MIN /B C:\PHP\apache\scripts\ctl.bat STOP)
if exist C:\PHP\ingres\scripts\ctl.bat (start /MIN /B C:\PHP\ingres\scripts\ctl.bat STOP)
if exist C:\PHP\mysql\scripts\ctl.bat (start /MIN /B C:\PHP\mysql\scripts\ctl.bat STOP)
if exist C:\PHP\postgresql\scripts\ctl.bat (start /MIN /B C:\PHP\postgresql\scripts\ctl.bat STOP)

:end

