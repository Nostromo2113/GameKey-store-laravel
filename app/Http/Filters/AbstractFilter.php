<?php


namespace App\Http\Filters;


use Illuminate\Database\Eloquent\Builder;

abstract class AbstractFilter implements FilterInterface
{
    /** @var array Параметры запроса для фильтрации */
    private $queryParams = [];

    /**
     * Конструктор.
     *
     * @param array $queryParams Параметры запроса, переданные для фильтрации
     */
    public function __construct(array $queryParams)
    {
        $this->queryParams = $queryParams;
    }

    /**
     * Возвращает массив колбэков для фильтрации.
     *
     * Каждый колбэк должен принимать два аргумента:
     * - @param Builder $builder: Построитель запросов Eloquent.
     * - @param mixed $value: Значение параметра фильтрации.
     *
     * @return array
     */
    abstract protected function getCallbacks(): array;

    /**
     * Применяет фильтры к построителю запросов.
     *
     * @param Builder $builder Построитель запросов Eloquent
     */
    public function apply(Builder $builder)
    {
        // Выполняет дополнительные действия перед применением фильтров
        $this->before($builder);

        // Применяет каждый колбэк, если соответствующий параметр запроса задан
        foreach ($this->getCallbacks() as $name => $callback) {
            if (isset($this->queryParams[$name])) {
                call_user_func($callback, $builder, $this->queryParams[$name]);
            }
        }
    }

    /**
     * Выполняет дополнительные действия перед применением фильтров.
     *
     * @param Builder $builder Построитель запросов Eloquent
     */
    protected function before(Builder $builder)
    {
        // По умолчанию ничего не делает. Может быть переопределен в дочерних классах.
    }

    /**
     * Возвращает значение параметра запроса по ключу.
     *
     * @param string $key Ключ параметра
     * @param mixed|null $default Значение по умолчанию, если параметр отсутствует
     * @return mixed|null
     */
    protected function getQueryParam(string $key, $default = null)
    {
        return $this->queryParams[$key] ?? $default;
    }

    /**
     * Удаляет указанные параметры из запроса.
     *
     * @param string[] $keys Ключи параметров, которые нужно удалить
     * @return AbstractFilter
     */
    protected function removeQueryParam(string ...$keys)
    {
        foreach ($keys as $key) {
            unset($this->queryParams[$key]);
        }

        return $this;
    }
}
