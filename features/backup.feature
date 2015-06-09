Feature: backup
	Manage your backups

Scenario: Simple backup
	Given I am in "tmp"
	And I have a file "tosave"
	And I have a Yaml config file:
		"""
		Files:
		    - tosave
		Backup Directory: backups
		"""
	When I run "bin/backup"
	Then I should have a file "backups/tosave"
	Then Remove current dir
