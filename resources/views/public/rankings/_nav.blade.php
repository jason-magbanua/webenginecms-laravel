<div class="btn-group" style="margin-bottom:15px;">
    <a href="{{ route('rankings.level') }}"       class="btn btn-sm {{ request()->routeIs('rankings.level')       ? 'btn-primary' : 'btn-default' }}">Level</a>
    <a href="{{ route('rankings.resets') }}"      class="btn btn-sm {{ request()->routeIs('rankings.resets')      ? 'btn-primary' : 'btn-default' }}">Resets</a>
    <a href="{{ route('rankings.grandresets') }}" class="btn btn-sm {{ request()->routeIs('rankings.grandresets') ? 'btn-primary' : 'btn-default' }}">Grand Resets</a>
    <a href="{{ route('rankings.master') }}"      class="btn btn-sm {{ request()->routeIs('rankings.master')      ? 'btn-primary' : 'btn-default' }}">Master</a>
    <a href="{{ route('rankings.guilds') }}"      class="btn btn-sm {{ request()->routeIs('rankings.guilds')      ? 'btn-primary' : 'btn-default' }}">Guilds</a>
    <a href="{{ route('rankings.killers') }}"     class="btn btn-sm {{ request()->routeIs('rankings.killers')     ? 'btn-primary' : 'btn-default' }}">PK Killers</a>
    <a href="{{ route('rankings.votes') }}"       class="btn btn-sm {{ request()->routeIs('rankings.votes')       ? 'btn-primary' : 'btn-default' }}">Votes</a>
    <a href="{{ route('rankings.gens') }}"        class="btn btn-sm {{ request()->routeIs('rankings.gens')        ? 'btn-primary' : 'btn-default' }}">Gens</a>
    <a href="{{ route('rankings.online') }}"      class="btn btn-sm {{ request()->routeIs('rankings.online')      ? 'btn-primary' : 'btn-default' }}">Online</a>
</div>
