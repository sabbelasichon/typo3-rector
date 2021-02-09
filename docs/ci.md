# Continuous integration

## GitLab CI

```yaml
include:
  - typo3-rector: 'https://raw.githubusercontent.com/sabbelasichon/typo3-rector/master/gitlab-template.yml'

typo3-rector:
  extends: .typo3-rector
  stage: test

typo3-rector-dev-master:
  extends: .typo3-rector
  stage: test
  variables:
    TYPO3_RECTOR_VERSION: dev-master
```
