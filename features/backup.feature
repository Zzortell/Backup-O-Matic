Feature: backup-o-matic
	CLI Application to manage your backups

Background:
	Given I am in "tmp"

Scenario: Simple backup
	Given I have a file "tosave"
	And I have a Yaml config file:
		"""
		Files:
		    - tosave
		Backup Directory: backups
		"""
	When I run "../bin/backup-o-matic backup"
	Then I should have a file "backups/tosave"
	And I should have a file "backups/BACKUP.md"

Scenario: Folder backup
	Given I have a folder "to_backup"
	And I have a file "to_backup/tosave"
	And I have a Yaml config file:
		"""
		Files:
		    - to_backup
		Backup Directory: backups
		"""
	When I run "../bin/backup-o-matic backup"
	Then I should have a file "backups/to_backup/tosave"

Scenario: Complex selector
	Given I have a file "tosave"
	And I have a Yaml config file:
		"""
		Files:
		    - *
		Backup Directory: backups
		"""
	When I run "../bin/backup-o-matic backup"
	Then I should have a file "backups/tosave"

Scenario: Backup dir's date templating
	Given I have a file "tosave"
	And I have a Yaml config file:
		"""
		Files:
		    - *
		Backup Directory: %date:"Y-m-d_H-i"%_backup
		"""
	When I run "../bin/backup-o-matic backup"
	Then I should have a folder matching "#^\d{4}-\d{2}-\d{2}_\d{2}-\d{2}_backup$#"
