please create a branch with your name

-add an .env with the following: 
SESSION_DRIVER=file
your db credentials

-run php artisan key:generate
-run php artisan config:cache
-run php artisan migrate
-run php artisan db:seed (this will create 3 surveys)

routes: 
/surveys (list of surveys, created with the seeder)
/surveys/1 (view of person answering survey [respondent])
/surveys/1/edit (view to add/edit questions)


https://github.com/fueljony/tech_project

https://www.figma.com/design/1ITZlhG4bxas5BF7OTr9xJ/Survey-Designer-Test?node-id=0-1&p=f&t=eGM0jIZSvPbKf80w-0

example of the setup in our platform
https://app.screencast.com/PdzRuZ4Nd5IV4

example of the respondent's view
https://app.screencast.com/ZLmMRfFeWU5UN

upload the code to your branch
