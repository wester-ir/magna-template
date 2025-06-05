<form class="search" action="{{ route('client.search') }}" method="GET" onsubmit="return this.search.value.length > 2">
    <input type="text" name="search" maxlength="50" placeholder="جستجو کنید...">
    <button class="search-btn"><i class="fi fi-rr-search"></i></button>
</form>
