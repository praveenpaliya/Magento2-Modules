# AWS Components
Running this Quick Start with default parameters for a new VPC deploys and configures the following AWS components in the AWS Cloud:

 - An User request points to Varnish Cache
 - Use Nginix to resolve the ELB DNS Name
 - A VPC that spans Availability Zone. Availability Zone is configured with a private and a public subnet.
 - In a public subnet, a bastion host to provide Secure Shell (SSH) access to the Magento web servers.
 - AWS-managed network address translation (NAT) gateways deployed into the public subnets and configured with an Elastic IP address for outbound internet connectivity. The NAT gateways are used for internet access for all EC2 instances launched within the private network.
 - An Amazon RDS for MySQL deployed via Amazon RDS in the first private subnet.
 - An Amazon ElastiCache cluster with the Redis cache engine launched in the private subnets.
 - EC2 web server instances launched in the private subnets.
 - Offload media assets request to Amazon S3 and Cloud Front.
 - An IAM instance role with fine-grained permissions for access to AWS services necessary for the deployment process.
 - Appropriate security groups for each instance or function to restrict access to only necessary protocols and ports. For example, access to HTTP server ports on Amazon EC2 web servers is limited to Elastic Load Balancing. The security groups also restrict access to Amazon RDS DB instances by web server instances.

# Magento Components
This Quick Start deploys Magento Open Source V 2.x with the following prerequisite software:

 - Operating system: Amazon Linux x86-64
 - Web server: NGINX
 - Database: Amazon RDS for MySQL 5.6 or above
 - Programming language: PHP 7.3 or above, including the required extensions
 
 Note* - I have not setup the Autoscaling group in this architecture if we have huge traffic, then Autoscaling group can be setup.
