![tests](https://github.com/jeyroik/extas-operations-jsonrpc-create/workflows/PHP%20Composer/badge.svg?branch=master&event=push)
![codecov.io](https://codecov.io/gh/jeyroik/extas-operations-jsonrpc-create/coverage.svg?branch=master)
<a href="https://github.com/phpstan/phpstan"><img src="https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat" alt="PHPStan Enabled"></a>

<a href="https://github.com/jeyroik/extas-installer/" title="Extas Installer v3"><img alt="Extas Installer v3" src="https://img.shields.io/badge/installer-v3-green"></a>
[![Latest Stable Version](https://poser.pugx.org/jeyroik/extas-operations-jsonrpc-create/v)](//packagist.org/packages/jeyroik/extas-jsonrpc)
[![Total Downloads](https://poser.pugx.org/jeyroik/extas-operations-jsonrpc-create/downloads)](//packagist.org/packages/jeyroik/extas-jsonrpc)
[![Dependents](https://poser.pugx.org/jeyroik/extas-operations-jsonrpc-create/dependents)](//packagist.org/packages/jeyroik/extas-jsonrpc)


# Описание

Create operation for JSON RPC server.

# Спецификация

```json
{
  "request": {
    "type": "object",
    "properties": {
      "data": {
      		"type": "object",
      		"items": {"type": "object"}
      	}
    }
  },
  "response" : {
    "type" : "object",
    "properties" : {
       "type": "object",
       "items": {"type": "mixed"}
    }
  }
}
```

# Пример запроса

```json
{
  "id": "2f5d0719-5b82-4280-9b3b-10f23aff226b",
  "method": "snuff.create",
  "params": {
    "data": {
      "name": "test"
    }
  }
}
```