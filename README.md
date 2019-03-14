# VaporWav-Project
VaporWav Website Project

All the credentials for the web server, EC2 instance, and database are in the credentials.txt file.
The .pem and .ppk files are the key pairs that you need for authentication.

How to log on to the server
Links:
1. Using putty on windows: https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/putty.html
2. Using ssh client: https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/AccessingInstancesLinux.html

Steps using putty (recommended):
1. Install Putty
2. Download vaporwav-key-pair.ppk from the repo
3. On the first screen enter public DNS for EC2 instance in the Host Name section
4. Leave Port as 22
5. Navigate to Auth menu under SSH menu in sidebar
6. In the bottom field click browse and add the .ppk file that you downloaded earlier
7. From here you can save the session by navigating back to the Session screen in the sidebar:
   - Enter a name for the session in the text field under Saved Sessions
   - Click save to save the session
8. Finally, click open and enter ec2-user under login as: (if you see a warning screen just click yes)
9. If you click open and all you see is a blank terminal, your ip address needs to be added to the security group.
   - Use this link to check your ip: http://checkip.amazonaws.com
   - Contact Ben or add it yourself through the AWS EC2 console
